require('dotenv').config();
const axios = require('axios');
const cheerio = require('cheerio');
const { GoogleGenerativeAI } = require('@google/generative-ai');

// Initialize Gemini
const genAI = new GoogleGenerativeAI(process.env.GEMINI_API_KEY);
// Use the requested model version
const model = genAI.getGenerativeModel({ model: 'gemini-2.5-flash' });

async function run() {
  try {
    console.log('🚀 Starting Worker (Gemini Powered)...');

    // 1. Fetch the Article from Laravel
    console.log('📥 Fetching article from Laravel...');
    const response = await axios.get(`${process.env.LARAVEL_API_URL}`);
    
    // Find the first article that hasn't been processed yet
    const articles = response.data.data || response.data;
    const article = articles.find(a => a.is_processed === false || a.is_processed === 0);

    if (!article) {
      console.log('✅ No unprocessed articles found.');
      return;
    }

    console.log(`✨ Processing: "${article.title}" (ID: ${article.id})`);

    // 2. Search Google for related content
    console.log('🔍 Searching Google...');
    const searchUrl = `https://serpapi.com/search.json?q=${encodeURIComponent(article.title)}&api_key=${process.env.SERPAPI_KEY}&engine=google`;
    const searchRes = await axios.get(searchUrl);
    
    // Get top 2 results (exclude own site)
    const organicResults = searchRes.data.organic_results || [];
    const topLinks = organicResults
      .filter(r => !r.link.includes('beyondchats.com'))
      .slice(0, 2);

    if (topLinks.length === 0) {
      console.log('❌ No valid Google results found.');
      return;
    }

    // 3. Scrape the external links
    let externalContext = '';
    let references = [];

    for (const linkObj of topLinks) {
      console.log(`🕷️ Scraping external source: ${linkObj.link}`);
      try {
        const extPage = await axios.get(linkObj.link, { timeout: 10000 });
        const $ = cheerio.load(extPage.data);
        
        // Grab paragraphs for context
        const text = $('p').map((i, el) => $(el).text()).get().join(' ').substring(0, 1500); 
        externalContext += `\nSOURCE (${linkObj.title}): ${text}\n`;
        references.push({ title: linkObj.title, link: linkObj.link });
      } catch (err) {
        console.log(`⚠️ Failed to scrape ${linkObj.link}: ${err.message}`);
      }
    }

    // 4. Send to Gemini
    console.log('🤖 Sending to Gemini...');
    
    const prompt = `
            You are a technical editor. Rewrite the following blog article to be more comprehensive and professional.
            
            ORIGINAL ARTICLE TITLE: ${article.title}
            ORIGINAL CONTENT: ${article.content.substring(0, 2000)}... (truncated)

            ADDITIONAL CONTEXT FROM WEB:
            ${externalContext}

            INSTRUCTIONS:
            1. Combine the original content with insights from the web sources.
            2. Maintain a professional tone.
            3. Return ONLY the HTML body of the new article (no markdown code blocks, just the HTML tags). 
            4. At the very bottom, add a <h3>References</h3> section listing the sources used.
        `;

    const result = await model.generateContent(prompt);
    const responseText = result.response.text();

    // Clean up markdown code blocks if Gemini adds them (e.g., ```html ... ```)
    const cleanContent = responseText.replace(/```html|```/g, '').trim();

    // 5. Update Laravel
    console.log('💾 Updating Database via Laravel API...');
    await axios.put(`${process.env.LARAVEL_API_URL}/${article.id}`, {
      content: cleanContent,
      is_processed: true,
      references: references
    }, {
      headers: { 'X-API-Key': process.env.API_SECRET }
    });

    console.log('🎉 SUCCESS! Article updated with Gemini.');

  } catch (error) {
    console.error('🔥 Error:', error.response ? error.response.data : error.message);
  }
}

run();

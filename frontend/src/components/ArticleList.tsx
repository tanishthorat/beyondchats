import { Button } from '@heroui/react'
import { useArticles } from '../context/ArticleContext'

export default function ArticleList() {
  const { articles, loading, page, loadArticles, viewArticle, editArticle } = useArticles()

  console.log('Rendering ArticleList with articles:', articles)

  function stripHtml(input = '') {
    return input.replace(/<[^>]+>/g, '')
  }

  return (
    <div className="space-y-4">
      <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        {loading ? (
          <div>Loading...</div>
        ) : articles.length === 0 ? (
          <div className="col-span-full text-center p-8 bg-white rounded shadow">No articles found</div>
        ) : (
          articles.map((a) => (
            <article key={a.id} className="bg-white rounded shadow p-4 flex flex-col">
              <h3 className="text-lg font-semibold mb-2">{a.title}</h3>
              <p className="text-sm text-gray-600 flex-1">{((a.content || a.description) ? stripHtml(String(a.content || a.description)).slice(0, 140) : '').replace(/\s+/g,' ').trim()}{((a.content || a.description)||'').length>140?'…':''}</p>
              <div className="mt-4 flex gap-2">
                <Button onPress={() => { viewArticle(a.id); location.hash = `article/${a.id}` }}>View</Button>
                <Button color="secondary" onPress={() => editArticle(a)}>Edit</Button>
              </div>
            </article>
          ))
        )}
      </div>

      <div className="flex items-center justify-between mt-4">
        <Button disabled={page <= 1} onPress={() => loadArticles(Math.max(1, page - 1))}>Previous</Button>
        <div>Page {page}</div>
        <Button onPress={() => loadArticles(page + 1)}>Next</Button>
      </div>
    </div>
  )
}

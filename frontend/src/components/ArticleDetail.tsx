import { useEffect } from 'react'
import { Button } from '@heroui/react'
import { useArticles } from '../context/ArticleContext'

export default function ArticleDetail() {
  const { currentArticle: article, loading, deleteArticle, setView, editArticle, view } = useArticles()

  if (loading) return <div>Loading...</div>
  if (!article) return <div>Article not found</div>

  async function handleDelete() {
    if (!confirm('Delete this article?')) return
    try {
      await deleteArticle(article.id)
    } catch (e) {
      console.error(e)
      alert('Failed to delete')
    }
  }

  return (
    <div className="text-white rounded shadow p-6">
      <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-bold">{article.title}</h2>
          <div className="text-sm text-neutral-200">Updated: {article.updated_at || article.created_at}</div>
        </div>
        <div className="flex gap-2">
          <Button onClick={() => editArticle(article)}>Edit</Button>
          <Button appearance="danger" onClick={handleDelete}>Delete</Button>
        </div>
      </div>

      {article.image_url && (
        <div className="my-4">
          <img src={article.image_url} alt={article.title} className="w-full rounded" />
        </div>
      )}

      <hr className="my-4" />
      <div className="prose max-w-none" dangerouslySetInnerHTML={{ __html: String(article.content || article.description || '') }} />

      {article.references && article.references.length > 0 && (
        <div className="mt-6">
          <h3 className="font-semibold">References</h3>
          <ul className="list-disc pl-6">
            {article.references.map((r, i) => (
              <li key={i}><a href={r.link} target="_blank" rel="noreferrer" className="text-blue-600">{r.title || r.link}</a></li>
            ))}
          </ul>
        </div>
      )}

      <div className="mt-6">
        <Button appearance="secondary" onClick={() => { setView({ view: 'list' }); location.hash = '' }}>Back</Button>
      </div>
    </div>
  )
}

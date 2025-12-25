import { useMemo, useCallback } from 'react'
import { Button } from '@heroui/react'
import { useArticles } from '../context/ArticleContext'

// Constants
const FALLBACK_CONTENT = '<p>No content available</p>'

// Helper function (pure function outside component)
function sanitizeHtml(rawHtml: string): string {
  if (typeof window === 'undefined') return rawHtml

  try {
    const parser = new DOMParser()
    const doc = parser.parseFromString(rawHtml, 'text/html')

    // Remove inline SVGs and <object>/<embed> that may render large social icons
    doc.querySelectorAll('svg, object, embed').forEach((el) => el.remove())

    // Remove elements with common social-icon classes
    doc.querySelectorAll('[class*="social"], [class*="icon"]').forEach((el) => {
      const span = doc.createElement('span')
      span.textContent = ''
      span.setAttribute('class', 'removed-social-icon')
      el.replaceWith(span)
    })

    return doc.body.innerHTML
  } catch (error) {
    console.warn('sanitizeHtml failed:', error)
    return rawHtml
  }
}

function formatDate(dateString: string | undefined): string {
  if (!dateString) return 'Unknown date'

  try {
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    })
  } catch {
    return dateString
  }
}

export default function ArticleDetail() {
  const {
    currentArticle: article,
    loading,
    setView,
    // editArticle,
    // deleteArticle
  } = useArticles()

  // ✅ Move all hooks to the top BEFORE any conditional returns
  const rawContent = useMemo(() => {
    return String(article?.content || article?.description || '')
  }, [article?.content, article?.description])

  const safeHtml = useMemo(() => {
    if (!rawContent) return FALLBACK_CONTENT
    return sanitizeHtml(rawContent)
  }, [rawContent])

  const formattedDate = useMemo(() => {
    return formatDate(article?.updated_at || article?.created_at)
  }, [article?.updated_at, article?.created_at])


  // const handleDelete = useCallback(async () => {
  //   if (!article) return

  //   if (!window.confirm(`Are you sure you want to delete "${article.title}"?`)) {
  //     return
  //   }

  //   try {
  //     await deleteArticle(article.id)
  //     // Navigate back to list after successful deletion
  //     setView({ view: 'list' })
  //     window.location.hash = ''
  //   } catch (error) {
  //     console.error('Failed to delete article:', error)
  //     alert('Failed to delete article. Please try again.')
  //   }
  // }, [article, deleteArticle, setView])

  const handleBack = useCallback(() => {
    setView({ view: 'list' })
    window.location.hash = ''
  }, [setView])

  // const handleEdit = useCallback(() => {
  //   if (article) {
  //     editArticle(article)
  //   }
  // }, [article, editArticle])


  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-100">
        <div className="text-center">
          <div className="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600 mb-4" />
          <p className="text-gray-400">Loading article...</p>
        </div>
      </div>
    )
  }

  if (!article) {
    return (
      <div className="text-center p-12 bg-neutral-800 rounded-2xl">
        <div className="text-6xl mb-4">📄</div>
        <h3 className="text-xl font-semibold text-white mb-2">Article not found</h3>
        <p className="text-gray-400 mb-6">The article you're looking for doesn't exist or has been removed.</p>
        <Button color="secondary" onPress={handleBack}>
          Back to Articles
        </Button>
      </div>
    )
  }

  return (
    <article className="bg-neutral-800 text-white rounded-2xl shadow-lg p-6 md:p-8">
      {/* Header */}
      <header className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div className="flex-1">
          <h1 className="text-3xl md:text-4xl font-bold mb-2 leading-tight">
            {article.title}
          </h1>
          <time className="text-sm text-neutral-400" dateTime={article.updated_at || article.created_at}>
            Last updated: {formattedDate}
          </time>
        </div>

        {/* <div className="flex gap-2 shrink-0">
          <Button 
            onPress={handleEdit}
            color="primary"
            size="md"
            aria-label="Edit article"
          >
            Edit
          </Button>
          <Button 
            onPress={handleDelete}
            color="danger"
            size="md"
            aria-label="Delete article"
          >
            Delete
          </Button>
        </div> */}
      </header>

      {/* Featured Image */}
      {article.image_url && (
        <figure className="my-6">
          <img
            src={article.image_url}
            alt={article.title}
            className="w-full rounded-xl object-cover max-h-[500px]"
            loading="eager"
          />
        </figure>
      )}

      {/* Content */}
      <hr className="my-6 border-neutral-700" />

      <div
        className="prose prose-invert prose-lg max-w-none article-prose"
        dangerouslySetInnerHTML={{ __html: safeHtml }}
        role="article"
      />

      {/* References */}
      {article.references && article.references.length > 0 && (
        <aside className="mt-8 p-6 bg-neutral-900 rounded-xl border border-neutral-700">
          <h3 className="text-xl font-semibold mb-4 flex items-center gap-2">
            <span>📚</span>
            References
          </h3>
          <ul className="space-y-2">
            {article.references.map((reference, index) => (
              <li key={`ref-${index}`} className="flex items-start gap-2">
                <span className="text-neutral-500 flex-shrink-0">{index + 1}.</span>
                <a
                  href={reference.link}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="text-blue-400 hover:text-blue-300 hover:underline transition-colors break-words"
                >
                  {reference.title || reference.link}
                </a>
              </li>
            ))}
          </ul>
        </aside>
      )}

      {/* Footer Actions */}
      <footer className="mt-8 pt-6 border-t border-neutral-700">
        <Button
          color="secondary"
          onPress={handleBack}
          size="lg"
          startContent={
            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
          }
        >
          Back to Articles
        </Button>
      </footer>
    </article>
  )
}

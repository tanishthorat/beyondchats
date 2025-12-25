import { useArticles } from '../context/ArticleContext'
import { Skeleton } from '@heroui/react'
import { Pagination } from '@heroui/pagination'
import ArticleCard from './ArticleCard'

export default function ArticleList() {
  const { articles, loading, page, totalPages, loadArticles, viewArticle, editArticle } = useArticles()

  return (
    <div className="space-y-4">
      <div className="flex flex-col gap-4 px-4 sm:px-0">
        {loading ? (
          // Skeleton loader: show a few placeholder cards while loading
          Array.from({ length: 4 }).map((_, i) => ( 
            <div key={i} className="bg-neutral-800 rounded-2xl shadow-lg overflow-hidden flex flex-col sm:flex-row gap-4 p-4 animate-pulse">
              <Skeleton className="h-48 sm:h-32 sm:w-56 w-full bg-neutral-700 rounded-xl" />

              <div className="flex-1 flex flex-col justify-between">
                <div className="space-y-3">
                  <Skeleton className="h-6 bg-neutral-700 rounded w-3/4" />
                  <Skeleton className="h-4 bg-neutral-700 rounded w-full" />
                  <Skeleton className="h-4 bg-neutral-700 rounded w-5/6" />
                </div>

                <div className="mt-4 flex items-center justify-between">
                  <div className="flex items-center gap-3">
                    <Skeleton className="w-8 h-8 rounded-full bg-neutral-700" />
                    <div className="space-y-1">
                      <Skeleton className="h-4 bg-neutral-700 rounded w-24" />
                      <Skeleton className="h-3 bg-neutral-700 rounded w-16" />
                    </div>
                  </div>

                  <div className="flex items-center gap-2">
                    <Skeleton className="h-8 w-20 bg-neutral-700 rounded" />
                    <Skeleton className="h-8 w-10 bg-neutral-700 rounded" />
                  </div>
                </div>
              </div>
            </div>
          ))
        ) : articles.length === 0 ? (
          <div className="col-span-full text-center p-8 bg-white rounded shadow">No articles found</div>
        ) : (
          articles.map((a) => (
            <ArticleCard key={a.id} article={a} onView={(id) => { viewArticle(id); location.hash = `article/${id}` }} onEdit={(art) => editArticle(art)} />
          ))
        )}
      </div>

      <div className="flex items-center justify-center mt-4 dark">
        <Pagination total={totalPages} page={page} onChange={(p: number) => loadArticles(p)} siblings={1} boundaries={1} showControls />
      </div>
    </div>
  )
}

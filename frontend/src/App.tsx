import './App.css'
import { Button } from '@heroui/react'
import ArticleList from './components/ArticleList'
import ArticleDetail from './components/ArticleDetail'
import CreateEditArticle from './components/CreateEditArticle'
import { ArticleProvider, useArticles } from './context/ArticleContext'
import type { Article } from './types'

type ViewState = { view: 'list' } | { view: 'detail'; id: number } | { view: 'create' } | { view: 'edit'; article: Article }

function AppInner() {
  const { view, setView } = useArticles()

  return (
    <div className="min-h-screen bg-gray-50 p-4">
      <header className="max-w-5xl mx-auto flex items-center justify-between mb-6">
        <h1 className="text-2xl font-semibold">BeyondChats — Articles</h1>
        <div className="flex gap-2">
          <Button onPress={() => { setView({ view: 'create' }); location.hash = 'create' }}>New Article</Button>
          <Button onPress={() => { setView({ view: 'list' }); location.hash = '' }} color="secondary">Refresh</Button>
        </div>
      </header>

      <main className="max-w-5xl mx-auto">
        {view.view === 'list' && <ArticleList />}
        {view.view === 'detail' && <ArticleDetail />}
        {view.view === 'create' && <CreateEditArticle />}
        {view.view === 'edit' && <CreateEditArticle />}
      </main>
    </div>
  )
}

export default function App() {
  return (
    <ArticleProvider>
      <AppInner />
    </ArticleProvider>
  )
}

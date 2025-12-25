import './App.css'
import { Button, Image } from '@heroui/react'
import ArticleList from './components/ArticleList'
import ArticleDetail from './components/ArticleDetail'
import CreateEditArticle from './components/CreateEditArticle'
import { ArticleProvider, useArticles } from './context/ArticleContext'

function AppInner() {
  const { view, setView } = useArticles()

  return (
    <div className="bg-neutral-900">
      <div className="min-h-screen max-w-5xl mx-auto bg-neutral-900">
        <header className="max-w-5xl bg-neutral-900 p-3 fixed z-50 w-full align-middle mx-auto flex items-center justify-between mb-6">
          <h1 className="text-2xl flex gap-1 items-center text-white font-semibold"><Image className='h-8' src='https://beyondchats.com/wp-content/uploads/2023/12/Beyond_Chats_Logo-removebg-preview.png' /> <span>BeyondChats </span></h1>
          <div className="flex gap-2">
            <Button variant='ghost' color='warning' onPress={() => { setView({ view: 'create' }); location.hash = 'create' }}>New Article</Button>
            <Button onPress={() => { setView({ view: 'list' }); location.hash = '' }} color="primary">Refresh</Button>
          </div>
        </header>

        <main className="max-w-5xl mx-auto pt-20">
          {view.view === 'list' && <ArticleList />}
          {view.view === 'detail' && <ArticleDetail />}
          {view.view === 'create' && <CreateEditArticle />}
          {view.view === 'edit' && <CreateEditArticle />}
        </main>
      </div>
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

import './App.css'
import { Button, Image } from '@heroui/react'
import ArticleList from './components/ArticleList'
import ArticleDetail from './components/ArticleDetail'
import { ArticleProvider, useArticles } from './context/ArticleContext'

function AppInner() {
  const { view, setView } = useArticles()

  return (
    <div className="bg-neutral-900 p-4">
      <div className="min-h-screen bg-neutral-900">
        <header className="fixed z-50 top-0 left-0 right-0 bg-neutral-900/80 backdrop-blur-sm">
          <div className="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
            <h1 className="text-2xl flex gap-2 items-center text-white font-semibold"><Image className='h-8' src='https://beyondchats.com/wp-content/uploads/2023/12/Beyond_Chats_Logo-removebg-preview.png' /> <span>BeyondChats</span></h1>
            <div className="flex gap-2">

              <Button onPress={() => { setView({ view: 'list' }); location.hash = '' }} color="primary">Back</Button>
            </div>
          </div>
        </header>

        <main className="max-w-5xl mx-auto pt-24 px-4">
          {view.view === 'list' && <ArticleList />}
          {view.view === 'detail' && <ArticleDetail />}
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

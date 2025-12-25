import React, { createContext, useContext, useEffect, useState } from 'react'
import type { Article } from '../types'
import api from '../api'

type ViewState = { view: 'list' } | { view: 'detail'; id: number } | { view: 'create' } | { view: 'edit'; article?: Article }

type ContextValue = {
  articles: Article[]
  currentArticle: Article | null
  loading: boolean
  page: number
  totalPages: number
  view: ViewState
  loadArticles: (page?: number) => Promise<void>
  viewArticle: (id: number) => Promise<void>
  editArticle: (article: Article) => void
  createArticle: (payload: Partial<Article>) => Promise<void>
  updateArticle: (id: number, payload: Partial<Article>) => Promise<void>
  deleteArticle: (id: number) => Promise<void>
  setView: (v: ViewState) => void
}

const ArticleContext = createContext<ContextValue | undefined>(undefined)

export function ArticleProvider({ children }: { children: React.ReactNode }) {
  const [articles, setArticles] = useState<Article[]>([])
  const [currentArticle, setCurrentArticle] = useState<Article | null>(null)
  const [loading, setLoading] = useState(false)
  const [page, setPage] = useState(1)
  const [totalPages, setTotalPages] = useState(1)
  const [view, setView] = useState<ViewState>({ view: 'list' })

  async function loadArticles(p = 1) {
    setLoading(true)
    try {
      const res: any = await api.listArticles(p)
      const items = Array.isArray(res) ? res : res?.data || []
      setArticles(items)
      // extract pagination total pages if available
      const meta = res?.meta || res?.pagination || null
      const lastPage = meta?.last_page ?? meta?.total_pages ?? (meta?.total && meta?.per_page ? Math.ceil(meta.total / meta.per_page) : undefined)
      setTotalPages(lastPage ?? 1)
      setPage(p)
    } finally {
      setLoading(false)
    }
  }

  async function viewArticle(id: number) {
    setLoading(true)
    setView({ view: 'detail', id })
    try {
      const a = await api.getArticle(id)
      setCurrentArticle(a)
    } catch (e) {
      console.error(e)
      setCurrentArticle(null)
    } finally {
      setLoading(false)
    }
  }

  function editArticle(article: Article) {
    setCurrentArticle(article)
    setView({ view: 'edit', article })
  }

  async function createArticle(payload: Partial<Article>) {
    setLoading(true)
    try {
      await api.createArticle(payload)
      await loadArticles(1)
      setView({ view: 'list' })
    } finally {
      setLoading(false)
    }
  }

  async function updateArticle(id: number, payload: Partial<Article>) {
    setLoading(true)
    try {
      await api.updateArticle(id, payload)
      await loadArticles(page)
      setView({ view: 'list' })
    } finally {
      setLoading(false)
    }
  }

  async function deleteArticle(id: number) {
    setLoading(true)
    try {
      await api.deleteArticle(id)
      await loadArticles(page)
      setView({ view: 'list' })
      setCurrentArticle(null)
    } finally {
      setLoading(false)
    }
  }

  // Sync hash routing into the provider
  useEffect(() => {
    const onHash = () => {
      const hash = location.hash.replace('#', '')
      if (!hash) return setView({ view: 'list' })
      const parts = hash.split('/')
      if (parts[0] === 'article' && parts[1]) {
        viewArticle(Number(parts[1]))
      }
      if (parts[0] === 'create') setView({ view: 'create' })
    }
    window.addEventListener('hashchange', onHash)
    onHash()
    // initial load
    loadArticles(1)
    return () => window.removeEventListener('hashchange', onHash)
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])

  const value: ContextValue = {
    articles,
    currentArticle,
    loading,
    page,
    totalPages,
    view,
    loadArticles,
    viewArticle,
    editArticle,
    createArticle,
    updateArticle,
    deleteArticle,
    setView,
  }

  return <ArticleContext.Provider value={value}>{children}</ArticleContext.Provider>
}

export function useArticles() {
  const ctx = useContext(ArticleContext)
  if (!ctx) throw new Error('useArticles must be used within ArticleProvider')
  return ctx
}

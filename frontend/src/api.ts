import type { Article, PaginatedResponse } from './types'

const BASE = 'https://beyondchats-rt7s.onrender.com'

async function request<T>(path: string, opts: RequestInit = {}) {
  const res = await fetch(BASE + path, {
    headers: { 'Content-Type': 'application/json' },
    ...opts,
  })
  if (!res.ok) throw new Error(await res.text())
  const text = await res.text()
  if (!text) return null as unknown as T
  try {
    return JSON.parse(text) as T
  } catch {
    return text as unknown as T
  }
}

export async function listArticles(page = 1): Promise<PaginatedResponse<Article> | Article[]> {
  return request(`/api/articles?page=${page}`)
}

export async function getArticle(id: number): Promise<Article> {
  return request(`/api/articles/${id}`)
}

export async function createArticle(payload: Partial<Article>): Promise<Article> {
  return request('/api/articles', { method: 'POST', body: JSON.stringify(payload) })
}

export async function updateArticle(id: number, payload: Partial<Article>): Promise<Article> {
  return request(`/api/articles/${id}`, { method: 'PUT', body: JSON.stringify(payload) })
}

export async function deleteArticle(id: number): Promise<void> {
  await request(`/api/articles/${id}`, { method: 'DELETE' })
}

export default { listArticles, getArticle, createArticle, updateArticle, deleteArticle }

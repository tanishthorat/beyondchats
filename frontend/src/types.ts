export interface Article {
  id: number
  title: string
  description?: string | null
  content?: string | null
  image_url?: string | null
  source_url?: string | null
  is_processed?: boolean
  references?: Array<{ title?: string; link?: string }>
  created_at?: string
  updated_at?: string
}

export interface PaginatedResponse<T> {
  data: T[]
  meta?: any
}

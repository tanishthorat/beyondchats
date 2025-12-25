import React, { useCallback } from 'react'
import { Button, Image } from '@heroui/react'
import { Share2Icon } from 'lucide-react'
import type { Article } from '../types'

// Constants
const EXCERPT_LENGTH = 220
const DEFAULT_AUTHOR = 'Ava Johnson'
const DEFAULT_DATE = 'May 21, 2023'

// Helper functions
function stripHtml(input = ''): string {
  return input.replace(/<[^>]+>/g, '')
}

function formatDate(dateString: string | undefined): string {
  if (!dateString) return DEFAULT_DATE
  
  try {
    return new Date(dateString).toLocaleDateString('en-US', {
      month: 'long',
      day: 'numeric',
      year: 'numeric'
    })
  } catch {
    return DEFAULT_DATE
  }
}

function createExcerpt(content?: string | null , description?: string | null ): string {
  const rawText = content || description || ''
  if (!rawText) return ''
  
  return stripHtml(String(rawText))
    .replace(/\s+/g, ' ')
    .trim()
    .slice(0, EXCERPT_LENGTH)
}

function needsEllipsis(content?: string | null, description?: string | null): boolean {
  return (content || description || '').length > EXCERPT_LENGTH
}

interface ArticleCardProps {
  article: Article
  onView: (id: number) => void
  onEdit: (article: Article) => void
}

export default function ArticleCard({ article, onView }: ArticleCardProps) {
  const excerpt = createExcerpt(article.content, article.description)
  const showEllipsis = needsEllipsis(article.content, article.description)
  const formattedDate = formatDate(article.created_at)

  const handleShare = useCallback(async () => {
    // if (e) {
    //   e.stopPropagation()
    // }

    const shareUrl = `${window.location.origin}${window.location.pathname}#article/${article.id}`
    const shareTitle = article.title
    const shareText = stripHtml(article.description || article.content || '')

    try {
      // Modern Web Share API
      if (navigator.share) {
        await navigator.share({
          title: shareTitle,
          text: shareText,
          url: shareUrl,
        })
        return
      }

      // Fallback to clipboard
      if (navigator.clipboard?.writeText) {
        await navigator.clipboard.writeText(shareUrl)
        alert('Link copied to clipboard')
        return
      }

      // Final fallback - prompt dialog
      window.prompt('Copy this link:', shareUrl)
    } catch (error) {
      // User cancelled share or error occurred
      if (error instanceof Error && error.name !== 'AbortError') {
        console.error('Share failed:', error)
        alert('Could not share the article')
      }
    }
  }, [article.id, article.title, article.description, article.content])

  const handleViewClick = useCallback(() => {
    // e.stopPropagation()
    onView(article.id)
  }, [article.id, onView])

  const handleCardClick = useCallback(() => {
    onView(article.id)
  }, [article.id, onView])

  return (
    <article
      onClick={handleCardClick}
      className="bg-neutral-800 cursor-pointer text-neutral-100 rounded-2xl shadow-lg overflow-hidden flex flex-col sm:flex-row gap-4 p-4"
      role="article"
      aria-label={`Article: ${article.title}`}
    >
      {/* Article Image */}
      <div className="relative h-auto shrink-0 w-full sm:w-56">
        {article.image_url ? (
          <Image
            classNames={{ wrapper: "h-full" }}
            src={article.image_url}
            alt={article.title}
            className="h-48 sm:h-full w-full object-fill rounded-xl"
            loading="lazy"
          />
        ) : (
          <div
            className="h-48 sm:h-full w-full bg-neutral-800 rounded-xl flex items-center justify-center text-neutral-400"
            role="img"
            aria-label="No image available"
          >
            No Image
          </div>
        )}
      </div>

      {/* Article Content */}
      <div className="flex-1 flex flex-col justify-between">
        <div>
          <h3 className="text-xl font-semibold leading-snug">
            {article.title}
          </h3>
          <p className="text-sm text-neutral-300 mt-2">
            {excerpt}
            {showEllipsis && '…'}
          </p>
        </div>

        {/* Article Footer */}
        <div className="mt-4 flex items-center justify-between">
          {/* Author Info */}
          <div className="flex items-center gap-3">
            <Image
              src={article.image_url || '/'}
              alt={DEFAULT_AUTHOR}
              className="w-8 h-8 rounded-full object-cover border-2 border-neutral-800"
              loading="lazy"
            />
            <div className="text-sm text-neutral-300">
              <div className="font-medium">by {DEFAULT_AUTHOR}</div>
              <time className="text-xs text-neutral-400" dateTime={article.created_at}>
                {formattedDate}
              </time>
            </div>
          </div>

          {/* Action Buttons */}
          <div className="flex items-center gap-2">
            <Button
              size="sm"
              onPress={handleViewClick}
              className="rounded-full! p-2 bg-purple-600 text-white"
              aria-label={`View article: ${article.title}`}
            >
              View
            </Button>

            <Button
              size="sm"
              onPress={handleShare}
              className="rounded-full! p-2 bg-neutral-700 text-white"
              aria-label="Share article"
            >
              <Share2Icon size={16} aria-hidden="true" />
            </Button>
          </div>
        </div>
      </div>
    </article>
  )
}

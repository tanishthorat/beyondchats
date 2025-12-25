import React from 'react'
import { Button, Image } from '@heroui/react'
import type { Article } from '../types'
import { Share2Icon } from 'lucide-react'

function stripHtml(input = '') {
    return input.replace(/<[^>]+>/g, '')
}

export default function ArticleCard({
    article,
    onView,
    onEdit,
}: {
    article: Article
    onView: (id: number) => void
    onEdit: (a: Article) => void
}) {
    const excerpt = (article.content || article.description || '')
        ? stripHtml(String(article.content || article.description)).replace(/\s+/g, ' ').trim().slice(0, 220)
        : ''

    async function handleShare(e?: React.MouseEvent<HTMLButtonElement>) {
        try {
            e?.stopPropagation()
        } catch (err: unknown) {
            // ignore if event is not provided or stopPropagation fails
            alert('Could not share the article')
            return
        }
        const url = `${location.origin}${location.pathname}#article/${article.id}`
        try {
            if (navigator.share) {
                await navigator.share({
                    title: article.title,
                    text: stripHtml(article.description || article.content || ''),
                    url,
                })
                // navigator.share resolves on success
            } else if (navigator.clipboard && navigator.clipboard.writeText) {
                await navigator.clipboard.writeText(url)
                alert('Link copied to clipboard')
            } else {
                // Last resort fallback
                window.prompt('Copy this link', url)
            }
        } catch (err) {
            // User cancelled or an error occurred
            console.debug('share failed', err)
            alert('Could not share the article')
        }
    }

    return (
        <article onClick={() => onView(article.id)} className="bg-neutral-800 cursor-pointer text-neutral-100 rounded-2xl shadow-lg overflow-hidden flex flex-col sm:flex-row gap-4 p-4">
            <div className="relative h-auto shrink-0 w-full sm:w-56">
                {article.image_url ? (
                    <Image classNames={{ wrapper: "h-full" }} src={article.image_url} alt={article.title} className="h-48 sm:h-full w-full object-fill rounded-xl" />
                ) : (
                    <div className="h-48 sm:h-full w-full bg-neutral-800 rounded-xl flex items-center justify-center text-neutral-400">No Image</div>
                )}
            </div>

            <div className="flex-1 flex flex-col justify-between">
                <div>
                    <h3 className="text-xl font-semibold leading-snug">{article.title}</h3>
                    <p className="text-sm text-neutral-300 mt-2">{excerpt}{((article.content || article.description) || '').length > 220 ? '…' : ''}</p>
                </div>

                <div className="mt-4 flex items-center justify-between">
                    <div className="flex items-center gap-3">
                        <Image src={article.image_url || '/'} alt="author" className="w-8 h-8 rounded-full object-cover border-2 border-neutral-800" />
                        <div className="text-sm text-neutral-300">
                            <div className="font-medium">by Ava Johnson</div>
                            <div className="text-xs text-neutral-400">{article.created_at ? new Date(article.created_at).toLocaleDateString() : 'May 21, 2023'}</div>
                        </div>
                    </div>

                    <div className="flex items-center gap-2">
                        <Button size='sm' color='primary' onPress={(e:any) => { e.stopPropagation(); onView(article.id) }} className="rounded-full! p-2 text-white">
                            View
                        </Button>

                        <Button size='sm' onPress={(e:any) => handleShare(e as React.MouseEvent)} className="rounded-full! p-2 bg-neutral-700 text-white">
                            <Share2Icon size={16} />
                        </Button>

                    </div>
                </div>
            </div>
        </article>
    )
}

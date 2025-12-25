import { useEffect, useState } from 'react'
import { Button } from '@heroui/react'
import { useArticles } from '../context/ArticleContext'

export default function CreateEditArticle() {
  const { currentArticle, createArticle, updateArticle, setView, view } = useArticles()
  const editing = view.view === 'edit' && currentArticle

  const [title, setTitle] = useState(currentArticle?.title || '')
  const [description, setDescription] = useState(currentArticle?.description || '')
  const [content, setContent] = useState(currentArticle?.content || '')
  const [saving, setSaving] = useState(false)

  useEffect(() => {
    setTitle(currentArticle?.title || '')
    setDescription(currentArticle?.description || '')
    setContent(currentArticle?.content || '')
  }, [currentArticle])

  async function handleSubmit(e?: React.FormEvent<HTMLFormElement>) {
    e?.preventDefault()
    setSaving(true)
    try {
      if (editing && currentArticle) {
        await updateArticle(currentArticle.id, { title, description, content })
      } else {
        await createArticle({ title, description, content })
      }
    } catch (e) {
      console.error(e)
      alert('Save failed')
    } finally {
      setSaving(false)
    }
  }

  return (
    <form onSubmit={handleSubmit} className="bg-white rounded shadow p-6 space-y-4">
      <div>
        <label className="block text-sm font-medium text-gray-700">Title</label>
        <input value={title} onChange={(e) => setTitle(e.target.value)} className="mt-1 block w-full border rounded p-2" />
      </div>

      <div>
        <label className="block text-sm font-medium text-gray-700">Short Description</label>
        <input value={description || ''} onChange={(e) => setDescription(e.target.value)} className="mt-1 block w-full border rounded p-2" />
      </div>

      <div>
        <label className="block text-sm font-medium text-gray-700">Content (HTML allowed)</label>
        <textarea value={content || ''} onChange={(e) => setContent(e.target.value)} rows={12} className="mt-1 block w-full border rounded p-2" />
      </div>

      <div className="flex gap-2">
        <Button type="submit" disabled={saving}>{saving ? 'Saving...' : editing ? 'Update' : 'Create'}</Button>
        <Button color="secondary" onClick={() => { setView({ view: 'list' }); location.hash = '' }}>Cancel</Button>
      </div>
    </form>
  )
}

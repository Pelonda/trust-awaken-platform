import { api } from '../../api/client'
import type { DocumentTemplate } from './types'

export async function getTemplates() {
  const { data } = await api.get<{
    data: DocumentTemplate[]
  }>('/documents/templates')

  return data
}

export async function getTemplate(
  uuid: string,
) {
  const { data } = await api.get(
    `/documents/templates/${uuid}`,
  )

  return data.data ?? data
}

export async function createTemplate(
  payload: any,
) {
  const { data } = await api.post(
    '/documents/templates',
    payload,
  )

  return data
}

export async function updateTemplate(
  uuid: string,
  payload: any,
) {
  const { data } = await api.put(
    `/documents/templates/${uuid}`,
    payload,
  )

  return data
}

export async function deleteTemplate(
  uuid: string,
) {
  const { data } = await api.delete(
    `/documents/templates/${uuid}`,
  )

  return data
}
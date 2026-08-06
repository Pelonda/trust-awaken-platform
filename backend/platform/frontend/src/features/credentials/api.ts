import { api } from '../../api/client'
import type { Credential } from './types'

export async function getCredentials() {
  const { data } = await api.get<{
    data: Credential[]
  }>('/credentials')

  return data
}

export async function createCredential(payload: any) {
  const { data } = await api.post(
    '/credentials',
    payload,
  )

  return data
}

export async function updateCredential(
  uuid: string,
  payload: any,
) {
  const { data } = await api.put(
    `/credentials/${uuid}`,
    payload,
  )

  return data
}

export async function deleteCredential(
  uuid: string,
) {
  const { data } = await api.delete(
    `/credentials/${uuid}`,
  )

  return data
}

export function previewCredential(
  uuid: string,
) {
  window.open(
    `${import.meta.env.VITE_API_URL}/credentials/${uuid}/preview`,
    '_blank'
  )
}

export async function downloadCredential(
  uuid: string,
) {
  const response = await api.get(
    `/credentials/${uuid}/download`,
    {
      responseType: 'blob',
    },
  )

  const url = window.URL.createObjectURL(
    new Blob([response.data])
  )

  const link = document.createElement('a')

  link.href = url

  link.download = `credential-${uuid}.pdf`

  document.body.appendChild(link)

  link.click()

  link.remove()

  window.URL.revokeObjectURL(url)
}
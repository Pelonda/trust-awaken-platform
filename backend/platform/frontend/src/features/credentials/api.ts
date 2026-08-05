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
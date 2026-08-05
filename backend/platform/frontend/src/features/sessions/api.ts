import { api } from '../../api/client'
import type { Session } from './types'

export async function getSessions() {
  const { data } = await api.get<{
    data: Session[]
  }>('/sessions')

  return data
}

export async function createSession(payload: any) {
  const { data } = await api.post(
    '/sessions',
    payload,
  )

  return data
}

export async function updateSession(
  uuid: string,
  payload: any,
) {
  const { data } = await api.put(
    `/sessions/${uuid}`,
    payload,
  )

  return data
}

export async function deleteSession(
  uuid: string,
) {
  const { data } = await api.delete(
    `/sessions/${uuid}`,
  )

  return data
}
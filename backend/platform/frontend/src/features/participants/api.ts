import { api } from '../../api/client'
import type { Participant } from './types'

export async function getParticipants() {
  const { data } = await api.get<{
    data: Participant[]
  }>('/participants')

  return data
}

export async function createParticipant(payload: any) {
  const { data } = await api.post(
    '/participants',
    payload,
  )

  return data
}

export async function updateParticipant(
  uuid: string,
  payload: any,
) {
  const { data } = await api.put(
    `/participants/${uuid}`,
    payload,
  )

  return data
}

export async function deleteParticipant(
  uuid: string,
) {
  const { data } = await api.delete(
    `/participants/${uuid}`,
  )

  return data
}
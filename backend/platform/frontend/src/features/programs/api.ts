import { api } from '../../api/client'
import type { Program } from './types'

export async function getPrograms() {
  const { data } = await api.get<{
    data: Program[]
  }>('/programs')

  return data
}

export async function getProgram(uuid: string) {
  const { data } = await api.get(`/programs/${uuid}`)
  return data.data ?? data
}

export async function createProgram(payload: any) {
  const { data } = await api.post('/programs', payload)
  return data
}

export async function updateProgram(
  uuid: string,
  payload: any,
) {
  const { data } = await api.put(
    `/programs/${uuid}`,
    payload,
  )

  return data
}

export async function deleteProgram(
  uuid: string,
) {
  const { data } = await api.delete(
    `/programs/${uuid}`,
  )

  return data
}
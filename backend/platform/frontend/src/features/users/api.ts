import { api } from '../../api/client'
import type { User } from './types'

export async function getUsers() {
  const { data } = await api.get<{
    data: User[]
  }>('/identity/users')

  return data
}

export async function createUser(payload: any) {
  const { data } = await api.post(
    '/identity/users',
    payload,
  )

  return data
}

export async function updateUser(
  uuid: string,
  payload: any,
) {
  const { data } = await api.put(
    `/identity/users/${uuid}`,
    payload,
  )

  return data
}

export async function deleteUser(
  uuid: string,
) {
  const { data } = await api.delete(
    `/identity/users/${uuid}`,
  )

  return data
}
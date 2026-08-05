import { api } from '../../api/client'
import type { Attendance } from './types'

export async function getAttendance() {
  const { data } = await api.get<{
    data: Attendance[]
  }>('/attendance')

  return data
}

export async function createAttendance(payload: any) {
  const { data } = await api.post(
    '/attendance',
    payload,
  )

  return data
}

export async function updateAttendance(
  uuid: string,
  payload: any,
) {
  const { data } = await api.put(
    `/attendance/${uuid}`,
    payload,
  )

  return data
}

export async function deleteAttendance(
  uuid: string,
) {
  const { data } = await api.delete(
    `/attendance/${uuid}`,
  )

  return data
}
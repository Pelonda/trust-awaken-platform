import { api } from '../../api/client'
import type { Organization } from './types'

export async function getOrganizations() {
  const { data } = await api.get<{
    data: Organization[]
  }>('/platform/organizations')

  return data
}

export async function getOrganization(
  uuid: string,
) {
  const { data } = await api.get(
    `/platform/organizations/${uuid}`,
  )

  return data.data ?? data
}

export async function createOrganization(
  payload: any,
) {
  const { data } = await api.post(
    '/platform/organizations',
    payload,
  )

  return data
}

export async function updateOrganization(
  uuid: string,
  payload: any,
) {
  const { data } = await api.put(
    `/platform/organizations/${uuid}`,
    payload,
  )

  return data
}

export async function deleteOrganization(
  uuid: string,
) {
  const { data } = await api.delete(
    `/platform/organizations/${uuid}`,
  )

  return data
}

export async function restoreOrganization(
  uuid: string,
) {
  const { data } = await api.post(
    `/platform/organizations/${uuid}/restore`,
  )

  return data
}
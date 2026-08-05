import { api } from './client'

export async function createProgram(data: any) {
  const response = await api.post('/programs', data)

  return response.data
}
import { api } from '../../api/client'

interface LoginPayload {
  email: string
  password: string
}

export async function login(
  payload: LoginPayload,
) {
  const { data } = await api.post(
    '/login',
    payload,
  )

  return data
}

export async function logout() {
  await api.post('/logout')
}

export async function me() {
  const { data } = await api.get('/me')

  return data
}
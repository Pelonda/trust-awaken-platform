import { api } from '../../api/client'

interface LoginPayload {
  email: string
  password: string
}

export async function login(
  payload: LoginPayload,
) {
  const { data } = await api.post(
    '/identity/login',
    payload,
  )

  return data
}

export async function logout() {
  await api.post('/identity/logout')
}

export async function me() {
  const { data } = await api.get('/identity/me')

  return data
}
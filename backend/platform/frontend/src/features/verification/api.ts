import { api } from '../../api/client'
import type { Verification } from './types'

export async function verifyCredential(
  verificationCode: string,
) {
  const { data } = await api.get<Verification>(
    `/verify/${verificationCode}`,
  )

  return data
}
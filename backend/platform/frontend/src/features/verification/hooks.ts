import { useQuery } from '@tanstack/react-query'

import { verifyCredential } from './api'

export function useVerification(
  verificationCode: string,
) {
  return useQuery({
    queryKey: ['verification', verificationCode],
    queryFn: () =>
      verifyCredential(verificationCode),
    enabled: verificationCode.length > 0,
  })
}
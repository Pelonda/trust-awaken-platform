import { useMutation } from '@tanstack/react-query'

import { createCredential } from './api'

export function useCreateCredential() {
  return useMutation({
    mutationFn: createCredential,
  })
}
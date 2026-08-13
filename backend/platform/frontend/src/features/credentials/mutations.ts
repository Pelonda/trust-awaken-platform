import {
  useMutation,
} from '@tanstack/react-query'

import {
  createCredential,
  issueFabricCredential,
} from './api'

export function useCreateCredential() {
  return useMutation({
    mutationFn:
      createCredential,
  })
}

export function useIssueFabricCredential() {
  return useMutation({
    mutationFn:
      issueFabricCredential,
  })
}
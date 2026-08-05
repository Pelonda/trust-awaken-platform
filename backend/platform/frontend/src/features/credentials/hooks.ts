import { useQuery } from '@tanstack/react-query'

import { getCredentials } from './api'

export function useCredentials() {
  return useQuery({
    queryKey: ['credentials'],
    queryFn: getCredentials,
  })
}
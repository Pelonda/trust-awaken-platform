import { useQuery } from '@tanstack/react-query'

import { getOrganizations } from './api'

export function useOrganizations() {
  return useQuery({
    queryKey: ['organizations'],
    queryFn: getOrganizations,
  })
}
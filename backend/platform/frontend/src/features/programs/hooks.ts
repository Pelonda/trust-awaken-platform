import { useQuery } from '@tanstack/react-query'

import { getPrograms } from './api'

export function usePrograms() {
  return useQuery({
    queryKey: ['programs'],
    queryFn: getPrograms,
  })
}
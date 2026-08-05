import { useQuery } from '@tanstack/react-query'

import { getParticipants } from './api'

export function useParticipants() {
  return useQuery({
    queryKey: ['participants'],
    queryFn: getParticipants,
  })
}
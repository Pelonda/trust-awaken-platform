import { useQuery } from '@tanstack/react-query'

import { getTemplates } from './api'

export function useTemplates() {
  return useQuery({
    queryKey: ['document-templates'],
    queryFn: getTemplates,
  })
}
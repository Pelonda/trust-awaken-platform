import { useMutation } from '@tanstack/react-query'

import { createTemplate } from './api'

export function useCreateTemplate() {
  return useMutation({
    mutationFn: createTemplate,
  })
}
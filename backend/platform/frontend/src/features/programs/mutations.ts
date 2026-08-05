import { useMutation } from '@tanstack/react-query'

import { createProgram } from './api'

export function useCreateProgram() {
  return useMutation({
    mutationFn: createProgram,
  })
}
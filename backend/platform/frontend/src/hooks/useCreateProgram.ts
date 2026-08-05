import { useMutation } from '@tanstack/react-query'

import { createProgram } from '../api/createProgram'

export function useCreateProgram() {
  return useMutation({
    mutationFn: createProgram,
  })
}
import { useMutation } from '@tanstack/react-query'

import { createUser } from './api'

export function useCreateUser() {
  return useMutation({
    mutationFn: createUser,
  })
}
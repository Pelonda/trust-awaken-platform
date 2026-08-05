import { useMutation } from '@tanstack/react-query'

import { createSession } from './api'

export function useCreateSession() {
  return useMutation({
    mutationFn: createSession,
  })
}
import { useMutation } from '@tanstack/react-query'

import {
  createParticipant,
} from './api'

export function useCreateParticipant() {
  return useMutation({
    mutationFn: createParticipant,
  })
}
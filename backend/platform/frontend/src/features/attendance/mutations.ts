import { useMutation } from '@tanstack/react-query'

import { createAttendance } from './api'

export function useCreateAttendance() {
  return useMutation({
    mutationFn: createAttendance,
  })
}
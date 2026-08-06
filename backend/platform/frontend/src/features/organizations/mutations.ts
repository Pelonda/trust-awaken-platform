import { useMutation } from '@tanstack/react-query'

import {
  createOrganization,
} from './api'

export function useCreateOrganization() {
  return useMutation({
    mutationFn: createOrganization,
  })
}
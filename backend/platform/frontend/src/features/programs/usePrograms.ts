import { useQuery } from '@tanstack/react-query'

import { getPrograms } from './programs'

export function usePrograms() {
    return useQuery({
        queryKey: ['programs'],
        queryFn: getPrograms,
    })
}
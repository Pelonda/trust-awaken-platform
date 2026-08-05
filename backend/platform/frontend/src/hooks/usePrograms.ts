import { useQuery } from '@tanstack/react-query'

import { getPrograms } from '../api/programs'

export function usePrograms() {
    return useQuery({
        queryKey: ['programs'],
        queryFn: getPrograms,
    })
}
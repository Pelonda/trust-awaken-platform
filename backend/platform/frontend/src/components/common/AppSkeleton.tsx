import {
  Skeleton,
  Stack,
} from '@mui/material'

export default function AppSkeleton() {
  return (
    <Stack spacing={2}>
      <Skeleton
        variant="rounded"
        height={60}
      />

      <Skeleton
        variant="rounded"
        height={450}
      />
    </Stack>
  )
}
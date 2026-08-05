import { Grid } from '@mui/material'

import KpiCard from '../components/KpiCard'
import { useDashboard } from '../hooks/useDashboard'

export default function DashboardPage() {

  const { data } = useDashboard()

  return (
    <Grid container spacing={3}>

      <Grid size={{ xs: 12, md: 4 }}>
        <KpiCard
          title="Organizations"
          value={data?.organizations ?? 0}
        />
      </Grid>

      <Grid size={{ xs: 12, md: 4 }}>
        <KpiCard
          title="Programs"
          value={data?.programs ?? 0}
        />
      </Grid>

      <Grid size={{ xs: 12, md: 4 }}>
        <KpiCard
          title="Participants"
          value={data?.participants ?? 0}
        />
      </Grid>

      <Grid size={{ xs: 12, md: 4 }}>
        <KpiCard
          title="Sessions"
          value={data?.sessions ?? 0}
        />
      </Grid>

      <Grid size={{ xs: 12, md: 4 }}>
        <KpiCard
          title="Attendance"
          value={data?.attendance ?? 0}
        />
      </Grid>

      <Grid size={{ xs: 12, md: 4 }}>
        <KpiCard
          title="Credentials"
          value={data?.credentials ?? 0}
        />
      </Grid>

    </Grid>
  )
}
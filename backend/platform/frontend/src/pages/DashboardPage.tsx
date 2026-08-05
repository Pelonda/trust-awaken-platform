import { Grid } from '@mui/material'

import SchoolIcon from '@mui/icons-material/School'
import PeopleIcon from '@mui/icons-material/People'
import EventIcon from '@mui/icons-material/Event'
import FactCheckIcon from '@mui/icons-material/FactCheck'
import WorkspacePremiumIcon from '@mui/icons-material/WorkspacePremium'
import BusinessIcon from '@mui/icons-material/Business'
import DashboardChart from '../components/common/DashboardChart'
import RecentActivity from '../components/common/RecentActivity'

import StatCard from '../components/common/StatCard'

import { useDashboard } from '../hooks/useDashboard'

export default function DashboardPage() {

  const { data } = useDashboard()

  return (

    <Grid
      container
      spacing={3}
    >

      <Grid size={{ xs: 12, md: 4 }}>
        <StatCard
          title="Organizations"
          value={data?.organizations ?? 0}
          icon={<BusinessIcon />}
          color="#2563eb"
          change="+2%"
        />
      </Grid>

      <Grid size={{ xs: 12, md: 4 }}>
        <StatCard
          title="Programs"
          value={data?.programs ?? 0}
          icon={<SchoolIcon />}
          color="#0ea5e9"
          change="+4%"
        />
      </Grid>

      <Grid size={{ xs: 12, lg: 8 }}>
  <DashboardChart />
</Grid>

<Grid size={{ xs: 12, lg: 4 }}>
  <RecentActivity />
</Grid>

      <Grid size={{ xs: 12, md: 4 }}>
        <StatCard
          title="Participants"
          value={data?.participants ?? 0}
          icon={<PeopleIcon />}
          color="#22c55e"
          change="+8%"
        />
      </Grid>

      <Grid size={{ xs: 12, md: 4 }}>
        <StatCard
          title="Sessions"
          value={data?.sessions ?? 0}
          icon={<EventIcon />}
          color="#a855f7"
          change="+1%"
        />
      </Grid>

      <Grid size={{ xs: 12, md: 4 }}>
        <StatCard
          title="Attendance"
          value={data?.attendance ?? 0}
          icon={<FactCheckIcon />}
          color="#f59e0b"
          change="+11%"
        />
      </Grid>

      <Grid size={{ xs: 12, md: 4 }}>
        <StatCard
          title="Credentials"
          value={data?.credentials ?? 0}
          icon={<WorkspacePremiumIcon />}
          color="#ef4444"
          change="+6%"
        />
      </Grid>

    </Grid>

  )

}
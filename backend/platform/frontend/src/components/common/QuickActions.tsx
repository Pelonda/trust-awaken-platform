import {
  Button,
  Card,
  CardContent,
  Grid,
  Typography,
} from '@mui/material'

import SchoolIcon from '@mui/icons-material/School'
import PeopleIcon from '@mui/icons-material/People'
import EventIcon from '@mui/icons-material/Event'
import WorkspacePremiumIcon from '@mui/icons-material/WorkspacePremium'

import { useNavigate } from 'react-router-dom'

export default function QuickActions() {

  const navigate = useNavigate()

  const actions = [

    {
      title: 'New Program',
      icon: <SchoolIcon />,
      path: '/programs',
    },

    {
      title: 'New Participant',
      icon: <PeopleIcon />,
      path: '/participants',
    },

    {
      title: 'New Session',
      icon: <EventIcon />,
      path: '/sessions',
    },

    {
      title: 'Issue Credential',
      icon: <WorkspacePremiumIcon />,
      path: '/credentials',
    },

  ]

  return (

    <Card
      elevation={0}
      sx={{
        borderRadius: 4,
        border: '1px solid #e5e7eb',
      }}
    >

      <CardContent>

        <Typography
          variant="h6"
          fontWeight={700}
          sx={{ mb: 3 }}
        >
          Quick Actions
        </Typography>

        <Grid
          container
          spacing={2}
        >

          {actions.map((action) => (

            <Grid
              key={action.title}
              size={{ xs: 12, md: 6 }}
            >

              <Button
                fullWidth
                size="large"
                startIcon={action.icon}
                variant="outlined"
                sx={{
                  justifyContent: 'flex-start',
                  height: 60,
                }}
                onClick={() =>
                  navigate(action.path)
                }
              >
                {action.title}
              </Button>

            </Grid>

          ))}

        </Grid>

      </CardContent>

    </Card>

  )

}
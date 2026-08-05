import {
  Card,
  CardContent,
  Stack,
  Typography,
} from '@mui/material'

export default function OrganizationSummary() {
  return (
    <Card
      elevation={0}
      sx={{
        borderRadius: 4,
        border: '1px solid #e5e7eb',
        height: '100%',
      }}
    >
      <CardContent>

        <Typography
          variant="h6"
          fontWeight={700}
          sx={{ mb: 3 }}
        >
          Organization
        </Typography>

        <Stack spacing={2}>

          <Typography>
            <strong>Name:</strong> Global CyberSafe Academy
          </Typography>

          <Typography>
            <strong>Programs:</strong> 10
          </Typography>

          <Typography>
            <strong>Participants:</strong> 50
          </Typography>

          <Typography>
            <strong>Sessions:</strong> 30
          </Typography>

          <Typography>
            <strong>Credentials:</strong> 50
          </Typography>

        </Stack>

      </CardContent>
    </Card>
  )
}
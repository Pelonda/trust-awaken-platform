import {
  Card,
  CardContent,
  Typography,
} from '@mui/material'

interface Props {
  title: string
  value: number | string
}

export default function KpiCard({
  title,
  value,
}: Props) {
  return (
    <Card elevation={3}>
      <CardContent>

        <Typography
          variant="body2"
          color="text.secondary"
        >
          {title}
        </Typography>

        <Typography
          variant="h4"
          fontWeight="bold"
        >
          {value}
        </Typography>

      </CardContent>
    </Card>
  )
}
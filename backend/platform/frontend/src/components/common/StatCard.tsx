import {
  Card,
  CardContent,
  Chip,
  Stack,
  Typography,
} from '@mui/material'

import TrendingUpIcon from '@mui/icons-material/TrendingUp'
import { motion } from 'framer-motion'
import type { ReactNode } from 'react'

interface Props {
  title: string
  value: number | string
  icon?: ReactNode
  color?: string
  change?: string
}

export default function StatCard({
  title,
  value,
  icon = <TrendingUpIcon />,
  color = '#2563eb',
  change = '+0%',
}: Props) {
  return (
    <motion.div
      whileHover={{
        y: -4,
      }}
      transition={{
        duration: 0.2,
      }}
    >
      <Card
        elevation={0}
        sx={{
          borderRadius: 4,
          border: '1px solid #e5e7eb',
          height: '100%',
        }}
      >
        <CardContent>

          <Stack
            direction="row"
            justifyContent="space-between"
            alignItems="center"
          >

            <Typography
              variant="body2"
              color="text.secondary"
            >
              {title}
            </Typography>

            <Stack
              justifyContent="center"
              alignItems="center"
              sx={{
                width: 42,
                height: 42,
                borderRadius: 2,
                bgcolor: `${color}15`,
                color,
              }}
            >
              {icon}
            </Stack>

          </Stack>

          <Typography
            variant="h3"
            fontWeight={700}
            sx={{
              mt: 2,
            }}
          >
            {value}
          </Typography>

          <Chip
            label={change}
            color="success"
            size="small"
            sx={{
              mt: 2,
            }}
          />

        </CardContent>
      </Card>
    </motion.div>
  )
}
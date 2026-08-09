import {
  Alert,
  Box,
  Button,
  Chip,
  Stack,
  Typography,
} from '@mui/material'

import QrCode2Icon from '@mui/icons-material/QrCode2'
import LockIcon from '@mui/icons-material/Lock'

import type {
  Canvas,
} from 'fabric'

import {
  FabricImage,
} from 'fabric'

interface Props {
  canvas: Canvas | null
}

export default function FabricQrPanel({
  canvas,
}: Props) {
  async function addVerificationQr() {
    if (!canvas) {
      return
    }

    const existing =
      canvas
        .getObjects()
        .find(
          object =>
            (object as any)
              .awakenType ===
            'verification-qr',
        )

    if (existing) {
      canvas.setActiveObject(
        existing,
      )

      canvas.requestRenderAll()

      return
    }

    try {
      const image =
        await FabricImage.fromURL(
          qrPlaceholder(),
        )

      image.set({
        left: 760,
        top: 450,

        scaleX:
          150 /
          (image.width || 150),

        scaleY:
          150 /
          (image.height || 150),

        borderColor:
          '#7c3aed',

        cornerColor:
          '#7c3aed',

        cornerStrokeColor:
          '#ffffff',

        cornerSize: 12,

        transparentCorners:
          false,

        padding: 2,
      })

      ;(image as any)
        .awakenType =
        'verification-qr'

      ;(image as any)
        .awakenVariable =
        '{{credential.verification_qr}}'

      ;(image as any)
        .awakenProtected =
        true

      canvas.add(
        image,
      )

      canvas.setActiveObject(
        image,
      )

      canvas.requestRenderAll()
    } catch (error) {
      console.error(
        'Unable to add verification QR:',
        error,
      )
    }
  }

  return (
    <Box>
      <Stack
        direction="row"
        spacing={1}
        alignItems="center"
      >
        <QrCode2Icon
          fontSize="small"
        />

        <Typography
          variant="subtitle1"
          fontWeight={700}
        >
          Verification
        </Typography>
      </Stack>

      <Typography
        variant="caption"
        color="text.secondary"
        sx={{
          display: 'block',
          mt: 0.5,
        }}
      >
        Secure credential
        verification fields.
      </Typography>

      <Button
        fullWidth
        variant="contained"
        startIcon={
          <QrCode2Icon />
        }
        disabled={!canvas}
        onClick={() =>
          void addVerificationQr()
        }
        sx={{
          mt: 2,
        }}
      >
        Verification QR
      </Button>

      <Chip
        size="small"
        icon={
          <LockIcon />
        }
        label="Backend controlled"
        sx={{
          mt: 1,
        }}
      />

      <Alert
        severity="info"
        sx={{
          mt: 2,
        }}
      >
        The QR shown in the
        designer is only a
        placeholder. A unique QR
        will be generated for each
        issued credential.
      </Alert>
    </Box>
  )
}

function qrPlaceholder(): string {
  const svg = `
    <svg
      xmlns="http://www.w3.org/2000/svg"
      width="300"
      height="300"
      viewBox="0 0 300 300"
    >
      <rect
        width="300"
        height="300"
        fill="white"
      />

      <rect
        x="12"
        y="12"
        width="276"
        height="276"
        rx="10"
        fill="none"
        stroke="#7c3aed"
        stroke-width="8"
      />

      <g
        fill="#111827"
      >
        <rect
          x="35"
          y="35"
          width="70"
          height="70"
        />

        <rect
          x="195"
          y="35"
          width="70"
          height="70"
        />

        <rect
          x="35"
          y="195"
          width="70"
          height="70"
        />

        <rect
          x="130"
          y="45"
          width="20"
          height="20"
        />

        <rect
          x="145"
          y="85"
          width="25"
          height="25"
        />

        <rect
          x="120"
          y="125"
          width="30"
          height="30"
        />

        <rect
          x="170"
          y="125"
          width="20"
          height="20"
        />

        <rect
          x="205"
          y="135"
          width="30"
          height="30"
        />

        <rect
          x="125"
          y="175"
          width="25"
          height="25"
        />

        <rect
          x="165"
          y="180"
          width="35"
          height="35"
        />

        <rect
          x="220"
          y="205"
          width="25"
          height="25"
        />

        <rect
          x="135"
          y="235"
          width="20"
          height="20"
        />
      </g>

      <rect
        x="50"
        y="50"
        width="40"
        height="40"
        fill="white"
      />

      <rect
        x="210"
        y="50"
        width="40"
        height="40"
        fill="white"
      />

      <rect
        x="50"
        y="210"
        width="40"
        height="40"
        fill="white"
      />
    </svg>
  `

  return (
    'data:image/svg+xml;charset=utf-8,' +
    encodeURIComponent(svg)
  )
}
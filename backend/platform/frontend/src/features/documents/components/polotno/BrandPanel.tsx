import {
  Box,
  Button,
  Divider,
  Stack,
  Typography,
} from '@mui/material'

import BusinessIcon from '@mui/icons-material/Business'
import ImageIcon from '@mui/icons-material/Image'
import DrawIcon from '@mui/icons-material/Draw'
import VerifiedIcon from '@mui/icons-material/Verified'
import BrandingWatermarkIcon from '@mui/icons-material/BrandingWatermark'

import { observer } from 'mobx-react-lite'
import { SectionTab } from 'polotno/side-panel'
import type { StoreType } from 'polotno/model/store'

interface Props {
  store: StoreType
}

type BrandAssetType =
  | 'logo'
  | 'signature'
  | 'seal'
  | 'watermark'

interface BrandAsset {
  type: BrandAssetType
  label: string
  variable: string
  width: number
  height: number
}

const BRAND_ASSETS: BrandAsset[] = [
  {
    type: 'logo',
    label: 'Organization Logo',
    variable: '{{organization.logo}}',
    width: 220,
    height: 100,
  },
  {
    type: 'signature',
    label: 'Authorized Signature',
    variable: '{{organization.signature}}',
    width: 220,
    height: 80,
  },
  {
    type: 'seal',
    label: 'Organization Seal',
    variable: '{{organization.seal}}',
    width: 120,
    height: 120,
  },
  {
    type: 'watermark',
    label: 'Watermark',
    variable: '{{organization.watermark}}',
    width: 300,
    height: 300,
  },
]

function createPlaceholder(
  label: string,
  variable: string,
) {
  const svg = `
    <svg
      xmlns="http://www.w3.org/2000/svg"
      width="600"
      height="300"
      viewBox="0 0 600 300"
    >
      <rect
        x="4"
        y="4"
        width="592"
        height="292"
        rx="18"
        fill="#f8fafc"
        stroke="#94a3b8"
        stroke-width="8"
        stroke-dasharray="20 14"
      />

      <text
        x="300"
        y="125"
        text-anchor="middle"
        font-family="Arial, sans-serif"
        font-size="34"
        font-weight="700"
        fill="#334155"
      >
        ${label}
      </text>

      <text
        x="300"
        y="180"
        text-anchor="middle"
        font-family="Arial, sans-serif"
        font-size="25"
        fill="#64748b"
      >
        ${variable}
      </text>
    </svg>
  `

  return (
    'data:image/svg+xml;charset=utf-8,' +
    encodeURIComponent(svg)
  )
}

export const BrandPanel =
  observer(({ store }: Props) => {

    function addBrandAsset(
      asset: BrandAsset,
    ) {
      const page =
        store.activePage ??
        store.pages[0]

      if (!page) {
        return
      }

      page.addElement({
        type: 'image',

        x: 100,
        y: 100,

        width: asset.width,
        height: asset.height,

        src: createPlaceholder(
          asset.label,
          asset.variable,
        ),

        custom: {
          trustAwakenType:
            `brand-${asset.type}`,

          protected: true,

          assetType:
            asset.type,

          variable:
            asset.variable,
        },
      })
    }

    return (
      <Box
        sx={{
          p: 2,
          height: '100%',
          overflow: 'auto',
        }}
      >
        <Typography
          variant="h6"
          fontWeight={700}
        >
          Brand
        </Typography>

        <Typography
          variant="body2"
          color="text.secondary"
          sx={{ mt: 0.5 }}
        >
          Insert organization-controlled branding into this template.
        </Typography>

        <Divider sx={{ my: 2 }} />

        <Stack spacing={1.25}>
          <Button
            variant="outlined"
            fullWidth
            startIcon={<ImageIcon />}
            onClick={() =>
              addBrandAsset(
                BRAND_ASSETS[0],
              )
            }
          >
            Organization Logo
          </Button>

          <Button
            variant="outlined"
            fullWidth
            startIcon={<DrawIcon />}
            onClick={() =>
              addBrandAsset(
                BRAND_ASSETS[1],
              )
            }
          >
            Authorized Signature
          </Button>

          <Button
            variant="outlined"
            fullWidth
            startIcon={<VerifiedIcon />}
            onClick={() =>
              addBrandAsset(
                BRAND_ASSETS[2],
              )
            }
          >
            Organization Seal
          </Button>

          <Button
            variant="outlined"
            fullWidth
            startIcon={
              <BrandingWatermarkIcon />
            }
            onClick={() =>
              addBrandAsset(
                BRAND_ASSETS[3],
              )
            }
          >
            Watermark
          </Button>
        </Stack>

        <Typography
          variant="caption"
          color="text.secondary"
          sx={{
            display: 'block',
            mt: 2,
          }}
        >
          Branding assets are resolved from the issuing organization when the final document is generated.
        </Typography>
      </Box>
    )
  })

export const BrandSection = {
  name: 'brand',

  Tab: (props: any) => (
    <SectionTab
      name="Brand"
      {...props}
    >
      <BusinessIcon
        sx={{
          fontSize: 22,
        }}
      />
    </SectionTab>
  ),

  Panel: BrandPanel,
}
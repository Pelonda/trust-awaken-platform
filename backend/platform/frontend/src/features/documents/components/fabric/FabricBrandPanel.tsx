import {
  Alert,
  Box,
  Button,
  CircularProgress,
  Divider,
  MenuItem,
  Select,
  Stack,
  TextField,
  Typography,
} from '@mui/material'

import ImageIcon from '@mui/icons-material/Image'
import UploadIcon from '@mui/icons-material/Upload'
import DrawIcon from '@mui/icons-material/Draw'
import VerifiedIcon from '@mui/icons-material/Verified'
import BrandingWatermarkIcon from '@mui/icons-material/BrandingWatermark'
import SaveIcon from '@mui/icons-material/Save'

import {
  useEffect,
  useRef,
  useState,
  type ChangeEvent,
} from 'react'

import {
  FabricImage,
  Rect,
  type Canvas,
  type FabricObject,
} from 'fabric'

import useBrand from '../../hooks/useBrand'

import AssetService, {
  type DocumentAssetType,
} from '../../services/AssetService'

interface Props {
  canvas: Canvas | null
}

type BrandAssetType =
  | 'logo'
  | 'signature'
  | 'seal'
  | 'watermark'
  | 'background'

type BrandPathField =
  | 'logo_path'
  | 'signature_path'
  | 'seal_path'
  | 'watermark_path'
  | 'background_path'

interface BrandAssetDefinition {
  type: BrandAssetType
  field: BrandPathField
  label: string
  width: number
  height: number
}

interface AwakenBrandObject
  extends FabricObject {
  awakenType?: string
  awakenVariable?: string
  awakenProtected?: boolean
  awakenBrandAsset?: string
  awakenAssetUuid?: string
  awakenAssetPath?: string
}

const FONTS = [
  'Arial',
  'Helvetica',
  'Inter',
  'Roboto',
  'Open Sans',
  'Lato',
  'Montserrat',
  'Georgia',
  'Times New Roman',
  'Verdana',
  'Trebuchet MS',
  'Courier New',
]

const ASSETS:
  BrandAssetDefinition[] = [
    {
      type: 'logo',
      field: 'logo_path',
      label: 'Logo',
      width: 220,
      height: 120,
    },

    {
      type: 'signature',
      field: 'signature_path',
      label: 'Signature',
      width: 220,
      height: 100,
    },

    {
      type: 'seal',
      field: 'seal_path',
      label: 'Seal',
      width: 140,
      height: 140,
    },

    {
      type: 'watermark',
      field: 'watermark_path',
      label: 'Watermark',
      width: 300,
      height: 300,
    },

    {
      type: 'background',
      field: 'background_path',
      label: 'Background',
      width: 1000,
      height: 650,
    },
  ]

export default function FabricBrandPanel({
  canvas,
}: Props) {
  const {
    brand,
    loading,
    saving,
    error,
    save,
  } = useBrand()

  const fileInputRef =
    useRef<HTMLInputElement | null>(
      null,
    )

  const [
    uploadingType,
    setUploadingType,
  ] = useState<
    BrandAssetType | null
  >(null)

  const [
    brandName,
    setBrandName,
  ] = useState('')

  const [
    logoPath,
    setLogoPath,
  ] = useState<string | null>(
    null,
  )

  const [
    signaturePath,
    setSignaturePath,
  ] = useState<string | null>(
    null,
  )

  const [
    sealPath,
    setSealPath,
  ] = useState<string | null>(
    null,
  )

  const [
    watermarkPath,
    setWatermarkPath,
  ] = useState<string | null>(
    null,
  )

  const [
    backgroundPath,
    setBackgroundPath,
  ] = useState<string | null>(
    null,
  )

  const [
    primaryColor,
    setPrimaryColor,
  ] = useState('#2563eb')

  const [
    secondaryColor,
    setSecondaryColor,
  ] = useState('#0f172a')

  const [
    accentColor,
    setAccentColor,
  ] = useState('#f59e0b')

  const [
    fontFamily,
    setFontFamily,
  ] = useState('Arial')

  const [
    localMessage,
    setLocalMessage,
  ] = useState<string | null>(
    null,
  )

  const [
    localError,
    setLocalError,
  ] = useState<string | null>(
    null,
  )

  useEffect(() => {
    if (!brand) {
      return
    }

    setBrandName(
      brand.brand_name ?? '',
    )

    setLogoPath(
      brand.logo_path,
    )

    setSignaturePath(
      brand.signature_path,
    )

    setSealPath(
      brand.seal_path,
    )

    setWatermarkPath(
      brand.watermark_path,
    )

    setBackgroundPath(
      brand.background_path,
    )

    setPrimaryColor(
      brand.primary_color ??
        '#2563eb',
    )

    setSecondaryColor(
      brand.secondary_color ??
        '#0f172a',
    )

    setAccentColor(
      brand.accent_color ??
        '#f59e0b',
    )

    setFontFamily(
      brand.font_family ??
        'Arial',
    )
  }, [brand])

  function getAssetPath(
    type: BrandAssetType,
  ): string | null {
    switch (type) {
      case 'logo':
        return logoPath

      case 'signature':
        return signaturePath

      case 'seal':
        return sealPath

      case 'watermark':
        return watermarkPath

      case 'background':
        return backgroundPath
    }
  }

  function setAssetPath(
    type: BrandAssetType,
    path: string | null,
  ) {
    switch (type) {
      case 'logo':
        setLogoPath(path)
        break

      case 'signature':
        setSignaturePath(path)
        break

      case 'seal':
        setSealPath(path)
        break

      case 'watermark':
        setWatermarkPath(path)
        break

      case 'background':
        setBackgroundPath(path)
        break
    }
  }

  function openUploader(
    type: BrandAssetType,
  ) {
    setUploadingType(type)

    /*
     * Give React time to update
     * uploadingType before opening
     * the file picker.
     */
    window.setTimeout(
      () => {
        fileInputRef.current?.click()
      },
      0,
    )
  }

  async function handleUpload(
    event:
      ChangeEvent<HTMLInputElement>,
  ) {
    const file =
      event.target.files?.[0]

    event.target.value = ''

    if (
      !file ||
      !uploadingType
    ) {
      return
    }

    const type =
      uploadingType

    try {
      setLocalError(null)
      setLocalMessage(null)

      const asset =
        await AssetService.create(
          file,
          type as
            DocumentAssetType,
          file.name,
        )

      setAssetPath(
        type,
        asset.path,
      )

      /*
       * Immediately persist the new
       * asset reference to document_brands.
       */
      const payload =
        buildBrandPayload({
          brandName,
          logoPath:
            type === 'logo'
              ? asset.path
              : logoPath,

          signaturePath:
            type === 'signature'
              ? asset.path
              : signaturePath,

          sealPath:
            type === 'seal'
              ? asset.path
              : sealPath,

          watermarkPath:
            type === 'watermark'
              ? asset.path
              : watermarkPath,

          backgroundPath:
            type === 'background'
              ? asset.path
              : backgroundPath,

          primaryColor,
          secondaryColor,
          accentColor,
          fontFamily,
        })

      await save(
        payload,
      )

      setLocalMessage(
        `${labelFor(type)} uploaded.`,
      )
    } catch (exception) {
      console.error(
        'Unable to upload brand asset:',
        exception,
      )

      setLocalError(
        exception instanceof Error
          ? exception.message
          : 'Unable to upload brand asset.',
      )
    } finally {
      setUploadingType(null)
    }
  }

  async function saveBrand() {
    try {
      setLocalError(null)
      setLocalMessage(null)

      await save(
        buildBrandPayload({
          brandName,
          logoPath,
          signaturePath,
          sealPath,
          watermarkPath,
          backgroundPath,
          primaryColor,
          secondaryColor,
          accentColor,
          fontFamily,
        }),
      )

      setLocalMessage(
        'Brand saved.',
      )
    } catch (exception) {
      console.error(
        'Unable to save brand:',
        exception,
      )

      setLocalError(
        exception instanceof Error
          ? exception.message
          : 'Unable to save brand.',
      )
    }
  }

  async function addAsset(
    definition:
      BrandAssetDefinition,
  ) {
    if (!canvas) {
      return
    }

    const path =
      getAssetPath(
        definition.type,
      )

    if (!path) {
      setLocalError(
        `${definition.label} has not been uploaded.`,
      )

      return
    }

    /*
     * Only one organization-controlled
     * instance of each Brand asset type
     * should exist on a template.
     */
    const existing =
      canvas
        .getObjects()
        .find(
          object =>
            (
              object as
                AwakenBrandObject
            ).awakenType ===
            `brand-${definition.type}`,
        )

    if (existing) {
      canvas.setActiveObject(
        existing,
      )

      canvas.requestRenderAll()

      return
    }

    const source =
      resolveAssetUrl(
        path,
      )

    if (!source) {
      return
    }

    try {
      setLocalError(null)

      /*
       * Do not force crossOrigin here.
       * We already confirmed public
       * Laravel storage loads correctly
       * in Fabric without it.
       */
      const image =
        await FabricImage.fromURL(
          source,
        )

      const originalWidth =
        image.width || 1

      const originalHeight =
        image.height || 1

      let scale =
        Math.min(
          definition.width /
            originalWidth,

          definition.height /
            originalHeight,
        )

      /*
       * Do not enlarge normal Brand
       * assets beyond their native size.
       * Background is allowed to scale
       * up to cover the canvas.
       */
      if (
        definition.type !==
        'background'
      ) {
        scale =
          Math.min(
            scale,
            1,
          )
      }

      image.set({
        left:
          definition.type ===
          'background'
            ? 0
            : 100,

        top:
          definition.type ===
          'background'
            ? 0
            : 100,

        scaleX:
          scale,

        scaleY:
          scale,

        borderColor:
          '#0f766e',

        cornerColor:
          '#0f766e',

        cornerStrokeColor:
          '#ffffff',

        cornerSize:
          12,

        transparentCorners:
          false,

        padding:
          2,
      })

      const awakenImage =
        image as
          AwakenBrandObject

      awakenImage.awakenType =
        `brand-${definition.type}`

      awakenImage.awakenVariable =
        `{{organization.${definition.type}}}`

      awakenImage.awakenProtected =
        true

      awakenImage.awakenBrandAsset =
        definition.type

      awakenImage.awakenAssetPath =
        path

      canvas.add(
        image,
      )

      if (
        definition.type ===
        'background'
      ) {
        canvas.sendObjectToBack(
          image,
        )
      }

      canvas.setActiveObject(
        image,
      )

      image.setCoords()

      canvas.requestRenderAll()
    } catch (exception) {
      console.error(
        `Unable to load ${definition.label}:`,
        exception,
      )

      setLocalError(
        `Unable to load ${definition.label}.`,
      )
    }
  }

  function addColor(
    color: string,
  ) {
    if (!canvas) {
      return
    }

    const rectangle =
      new Rect({
        left: 100,
        top: 100,

        width: 180,
        height: 80,

        fill:
          color,

        stroke:
          '#cbd5e1',

        strokeWidth:
          1,

        rx: 4,
        ry: 4,

        borderColor:
          '#2563eb',

        cornerColor:
          '#2563eb',

        cornerStrokeColor:
          '#ffffff',

        cornerSize:
          12,

        transparentCorners:
          false,
      })

    canvas.add(
      rectangle,
    )

    canvas.setActiveObject(
      rectangle,
    )

    canvas.requestRenderAll()
  }

  if (loading) {
    return (
      <Box
        sx={{
          display:
            'flex',

          justifyContent:
            'center',

          py: 3,
        }}
      >
        <CircularProgress
          size={26}
        />
      </Box>
    )
  }

  return (
    <Box>
      <Typography
        variant="subtitle1"
        fontWeight={700}
      >
        Brand
      </Typography>

      <Typography
        variant="caption"
        color="text.secondary"
      >
        Organization document identity
      </Typography>

      {(error || localError) && (
        <Alert
          severity="error"
          sx={{
            mt: 1,
          }}
        >
          {localError ?? error}
        </Alert>
      )}

      {localMessage && (
        <Alert
          severity="success"
          sx={{
            mt: 1,
          }}
        >
          {localMessage}
        </Alert>
      )}

      {!brand && (
        <Alert
          severity="info"
          sx={{
            mt: 1,
          }}
        >
          No Brand has been configured
          yet. Upload an asset or save
          the settings below to create
          one.
        </Alert>
      )}

      <TextField
        fullWidth
        size="small"
        label="Brand name"
        value={brandName}
        onChange={
          event =>
            setBrandName(
              event.target.value,
            )
        }
        sx={{
          mt: 2,
        }}
      />

      <Divider
        sx={{
          my: 2,
        }}
      />

      <Typography
        variant="subtitle2"
        fontWeight={700}
      >
        Brand Assets
      </Typography>

      <input
        ref={fileInputRef}
        type="file"
        accept="image/png,image/jpeg,image/webp,image/svg+xml"
        hidden
        onChange={
          handleUpload
        }
      />

      <Stack
        spacing={1}
        sx={{
          mt: 1,
        }}
      >
        {ASSETS.map(
          definition => {
            const configured =
              Boolean(
                getAssetPath(
                  definition.type,
                ),
              )

            const uploading =
              uploadingType ===
              definition.type

            return (
              <Box
                key={
                  definition.type
                }
              >
                <Typography
                  variant="caption"
                  fontWeight={700}
                >
                  {definition.label}
                </Typography>

                <Stack
                  direction="row"
                  spacing={1}
                  sx={{
                    mt: 0.5,
                  }}
                >
                  <Button
                    size="small"
                    variant="outlined"
                    startIcon={
                      uploading
                        ? (
                            <CircularProgress
                              size={14}
                            />
                          )
                        : (
                            <UploadIcon />
                          )
                    }
                    disabled={
                      uploadingType !==
                        null ||
                      saving
                    }
                    onClick={() =>
                      openUploader(
                        definition.type,
                      )
                    }
                  >
                    {configured
                      ? 'Replace'
                      : 'Upload'}
                  </Button>

                  <Button
                    size="small"
                    variant={
                      configured
                        ? 'contained'
                        : 'outlined'
                    }
                    disabled={
                      !configured ||
                      !canvas
                    }
                    startIcon={
                      assetIcon(
                        definition.type,
                      )
                    }
                    onClick={() =>
                      void addAsset(
                        definition,
                      )
                    }
                  >
                    Place
                  </Button>
                </Stack>

                {configured && (
                  <Typography
                    variant="caption"
                    color="success.main"
                    sx={{
                      display:
                        'block',

                      mt: 0.5,
                    }}
                  >
                    Configured
                  </Typography>
                )}
              </Box>
            )
          },
        )}
      </Stack>

      <Divider
        sx={{
          my: 2,
        }}
      />

      <Typography
        variant="subtitle2"
        fontWeight={700}
      >
        Brand Colors
      </Typography>

      <Stack
        spacing={1}
        sx={{
          mt: 1,
        }}
      >
        <ColorControl
          label="Primary"
          value={primaryColor}
          onChange={
            setPrimaryColor
          }
          onPlace={() =>
            addColor(
              primaryColor,
            )
          }
        />

        <ColorControl
          label="Secondary"
          value={secondaryColor}
          onChange={
            setSecondaryColor
          }
          onPlace={() =>
            addColor(
              secondaryColor,
            )
          }
        />

        <ColorControl
          label="Accent"
          value={accentColor}
          onChange={
            setAccentColor
          }
          onPlace={() =>
            addColor(
              accentColor,
            )
          }
        />
      </Stack>

      <Divider
        sx={{
          my: 2,
        }}
      />

      <Typography
        variant="subtitle2"
        fontWeight={700}
      >
        Brand Font
      </Typography>

      <Select
        fullWidth
        size="small"
        value={fontFamily}
        sx={{
          mt: 1,
        }}
        onChange={
          event =>
            setFontFamily(
              String(
                event.target.value,
              ),
            )
        }
      >
        {FONTS.map(
          font => (
            <MenuItem
              key={font}
              value={font}
              sx={{
                fontFamily:
                  font,
              }}
            >
              {font}
            </MenuItem>
          ),
        )}
      </Select>

      <Button
        fullWidth
        variant="contained"
        startIcon={
          saving
            ? (
                <CircularProgress
                  size={16}
                  color="inherit"
                />
              )
            : (
                <SaveIcon />
              )
        }
        disabled={
          saving ||
          uploadingType !== null
        }
        onClick={() =>
          void saveBrand()
        }
        sx={{
          mt: 2,
        }}
      >
        Save Brand
      </Button>
    </Box>
  )
}

interface ColorControlProps {
  label: string
  value: string

  onChange(
    value: string,
  ): void

  onPlace(): void
}

function ColorControl({
  label,
  value,
  onChange,
  onPlace,
}: ColorControlProps) {
  return (
    <Box>
      <Typography
        variant="caption"
        fontWeight={700}
      >
        {label}
      </Typography>

      <Stack
        direction="row"
        spacing={1}
        sx={{
          mt: 0.5,
        }}
      >
        <TextField
          size="small"
          type="color"
          value={value}
          onChange={
            event =>
              onChange(
                event.target.value,
              )
          }
          sx={{
            width: 58,

            '& input': {
              p: 0.5,
              cursor:
                'pointer',
            },
          }}
        />

        <TextField
          size="small"
          value={value}
          onChange={
            event =>
              onChange(
                event.target.value,
              )
          }
          sx={{
            flex: 1,
          }}
        />

        <Button
          size="small"
          variant="outlined"
          onClick={
            onPlace
          }
        >
          Place
        </Button>
      </Stack>
    </Box>
  )
}

interface BrandPayloadValues {
  brandName: string

  logoPath:
    string | null

  signaturePath:
    string | null

  sealPath:
    string | null

  watermarkPath:
    string | null

  backgroundPath:
    string | null

  primaryColor: string
  secondaryColor: string
  accentColor: string

  fontFamily: string
}

function buildBrandPayload(
  values:
    BrandPayloadValues,
) {
  return {
    brand_name:
      values.brandName.trim()
        || null,

    logo_path:
      values.logoPath,

    signature_path:
      values.signaturePath,

    seal_path:
      values.sealPath,

    watermark_path:
      values.watermarkPath,

    background_path:
      values.backgroundPath,

    primary_color:
      values.primaryColor,

    secondary_color:
      values.secondaryColor,

    accent_color:
      values.accentColor,

    font_family:
      values.fontFamily,
  }
}

function labelFor(
  type: BrandAssetType,
): string {
  switch (type) {
    case 'logo':
      return 'Logo'

    case 'signature':
      return 'Signature'

    case 'seal':
      return 'Seal'

    case 'watermark':
      return 'Watermark'

    case 'background':
      return 'Background'
  }
}

function assetIcon(
  type: BrandAssetType,
) {
  switch (type) {
    case 'signature':
      return <DrawIcon />

    case 'seal':
      return <VerifiedIcon />

    case 'watermark':
      return (
        <BrandingWatermarkIcon />
      )

    case 'logo':
    case 'background':
      return <ImageIcon />
  }
}

function resolveAssetUrl(
  path: string | null,
): string | null {
  if (!path) {
    return null
  }

  if (
    path.startsWith(
      'data:',
    ) ||
    path.startsWith(
      'blob:',
    )
  ) {
    return path
  }

  const apiUrl =
    import.meta.env
      .VITE_API_URL as
      string | undefined

  if (!apiUrl) {
    return path
  }

  try {
    const apiOrigin =
      new URL(
        apiUrl,
      ).origin

    if (
      path.startsWith(
        'http://',
      ) ||
      path.startsWith(
        'https://',
      )
    ) {
      const parsed =
        new URL(
          path,
        )

      if (
        parsed.pathname
          .startsWith(
            '/storage/',
          )
      ) {
        return new URL(
          parsed.pathname,
          apiOrigin,
        ).toString()
      }

      return path
    }

    /*
     * document_brands stores the
     * document_assets path:
     *
     * document-assets/1/uuid.png
     *
     * Convert it to:
     *
     * /storage/document-assets/1/uuid.png
     */
    const storagePath =
      path.startsWith(
        '/storage/',
      )
        ? path
        : `/storage/${
            path.replace(
              /^\/+/,
              '',
            )
          }`

    return new URL(
      storagePath,
      apiOrigin,
    ).toString()
  } catch (exception) {
    console.error(
      'Unable to resolve Brand asset URL:',
      exception,
    )

    return path
  }
}
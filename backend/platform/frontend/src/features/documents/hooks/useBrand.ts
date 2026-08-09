import {
  useCallback,
  useEffect,
  useState,
} from 'react'

import BrandService, {
  type BrandPayload,
  type DocumentBrand,
} from '../services/BrandService'

export default function useBrand() {
  const [
    brand,
    setBrand,
  ] = useState<DocumentBrand | null>(
    null,
  )

  const [
    loading,
    setLoading,
  ] = useState(true)

  const [
    saving,
    setSaving,
  ] = useState(false)

  const [
    error,
    setError,
  ] = useState<string | null>(
    null,
  )

  const load = useCallback(
    async () => {
      try {
        setLoading(true)
        setError(null)

        const result =
          await BrandService.get()

        setBrand(result)
      } catch (exception) {
        console.error(exception)

        setError(
          exception instanceof Error
            ? exception.message
            : 'Unable to load brand.',
        )
      } finally {
        setLoading(false)
      }
    },
    [],
  )

  const save = useCallback(
    async (
      payload: BrandPayload,
    ) => {
      try {
        setSaving(true)
        setError(null)

        const result =
          await BrandService.save(
            payload,
          )

        setBrand(result)

        return result
      } catch (exception) {
        console.error(exception)

        const message =
          exception instanceof Error
            ? exception.message
            : 'Unable to save brand.'

        setError(message)

        throw exception
      } finally {
        setSaving(false)
      }
    },
    [],
  )

  useEffect(() => {
    void load()
  }, [load])

  return {
    brand,
    loading,
    saving,
    error,
    reload: load,
    save,
  }
}
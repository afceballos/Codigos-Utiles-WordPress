Sub GenerarDescripcionesEstaticas()
    Dim ws As Worksheet
    Dim lastRow As Long, i As Long
    Dim request As Object
    Dim apiKey As String, url As String
    Dim datosUnidos As String, prompt As String, response As String
    Dim colInicio As Integer, colFin As Integer, colDestino As Integer
    Dim celda As Range
    
    ' --- CONFIGURACIÓN ---
    apiKey = "API-PONER-AQUI" ' <--- TU API KEY
    url = "https://api.openai.com/v1/chat/completions"
    
    ' Configuración de columnas (A=1, P=16, Q=17)
    colInicio = 1 ' Columna A
    colFin = 16   ' Columna P
    colDestino = 17 ' Columna Q (Donde se escribirá la descripción)
    ' ---------------------

    Set ws = ActiveSheet
    ' Detectar hasta qué fila hay datos en la columna A
    lastRow = ws.Cells(ws.Rows.Count, colInicio).End(xlUp).Row
    
    ' Crear objeto HTTP una sola vez
    Set request = CreateObject("MSXML2.XMLHTTP")

    ' Recorrer fila por fila desde la 2 hasta el final
    For i = 2 To lastRow
        
        ' Verificar si ya existe una descripción en la columna Q para no gastar saldo
        If ws.Cells(i, colDestino).Value = "" Then
            
            ' 1. Unir datos de la fila (A hasta P)
            datosUnidos = ""
            Dim j As Integer
            For j = colInicio To colFin
                If Not IsError(ws.Cells(i, j).Value) And Len(ws.Cells(i, j).Value) > 0 Then
                    datosUnidos = datosUnidos & ws.Cells(i, j).Value & ", "
                End If
            Next j
            
            ' 2. Crear el Prompt (El mismo que definimos antes)
            prompt = "Actúa como experto inmobiliario en España. Crea perfil corporativo atractivo con estos datos: " & datosUnidos & ". Extensión máx 700 palabras."
            
            ' 3. Limpiar para JSON
            Dim textBody As String
            textBody = Replace(prompt, "\", "\\")
            textBody = Replace(textBody, """", "\""")
            textBody = Replace(textBody, vbCrLf, " ")
            
            Dim body As String
            body = "{""model"": ""gpt-4o-mini"", ""messages"": [{""role"": ""user"", ""content"": """ & textBody & """}], ""temperature"": 0.7}"

            ' 4. Llamar a la API
            ' Nota: Ponemos un pequeño "DoEvents" para que Excel no se congele visualmente
            DoEvents
            On Error Resume Next ' Evitar que se detenga si falla una fila
            
            With request
                .Open "POST", url, False
                .setRequestHeader "Content-Type", "application/json"
                .setRequestHeader "Authorization", "Bearer " + apiKey
                .send body
                
                If .Status = 200 Then
                    response = .responseText
                    ' Extracción simplificada para este script
                    Dim pStart As Integer, pEnd As Integer
                    pStart = InStr(response, """content"": """) + 11
                    Dim resultado As String
                    resultado = Split(Mid(response, pStart), """,")(0)
                    
                    ' Limpieza final
                    resultado = Replace(resultado, "\n", vbCrLf)
                    resultado = Replace(resultado, "\"" ", """")
                    resultado = Replace(resultado, "\\""", """")
                    
                    ' 5. ESCRIBIR EL RESULTADO FIJO EN LA CELDA
                    ws.Cells(i, colDestino).Value = resultado
                Else
                    ws.Cells(i, colDestino).Value = "Error API: " & .Status
                End If
            End With
            On Error GoTo 0
            
        End If
    Next i
    
    MsgBox "¡Proceso terminado! Las descripciones están listas en la columna Q."
End Sub

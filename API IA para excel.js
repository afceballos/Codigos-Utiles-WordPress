Function IA(instruccion As String, datos As Range) As String
    On Error GoTo ErrorHandler
    
    Dim request As Object
    Dim text As String, apiKey As String, response As String, url As String
    Dim body As String
    Dim celda As Range
    Dim datosUnidos As String
    
    ' --- CONFIGURACIÓN ---
    apiKey = "TU_API_KEY_AQUI" ' <--- PEGA TU API KEY OTRA VEZ
    url = "https://api.openai.com/v1/chat/completions"
    ' ---------------------

    ' 1. Unir el texto de todas las celdas del rango (A2:P2)
    datosUnidos = ""
    For Each celda In datos
        If Not IsError(celda.Value) And Len(celda.Value) > 0 Then
            ' Añadimos un espacio y coma entre cada dato
            datosUnidos = datosUnidos & CStr(celda.Value) & ", "
        End If
    Next celda

    ' 2. Crear el prompt final (Instrucción + Datos del rango)
    Dim promptFinal As String
    promptFinal = instruccion & " Información del producto: " & datosUnidos

    ' 3. Limpieza para JSON
    text = Replace(promptFinal, "\", "\\")
    text = Replace(text, """", "\""")
    text = Replace(text, vbCrLf, " ")
    
    ' 4. Configurar la petición
    Set request = CreateObject("MSXML2.XMLHTTP")
    body = "{""model"": ""gpt-4o-mini"", ""messages"": [{""role"": ""user"", ""content"": """ & text & """}], ""temperature"": 0.7}"

    ' 5. Enviar
    With request
        .Open "POST", url, False
        .setRequestHeader "Content-Type", "application/json"
        .setRequestHeader "Authorization", "Bearer " + apiKey
        .send body
        
        If .Status <> 200 Then
            IA = "Error API: " & .responseText
            Exit Function
        End If
        response = .responseText
    End With

    ' 6. Extraer respuesta (Método robusto)
    Dim partes() As String
    partes = Split(response, """content"": """)
    
    If UBound(partes) > 0 Then
        Dim resultadoBruto As String
        resultadoBruto = partes(1)
        Dim corte() As String
        corte = Split(resultadoBruto, """,")
        Dim textoFinal As String
        textoFinal = corte(0)
        
        If InStr(textoFinal, """}") > 0 Then textoFinal = Split(textoFinal, """}")(0)

        textoFinal = Replace(textoFinal, "\n", vbCrLf)
        textoFinal = Replace(textoFinal, "\"" ", """")
        textoFinal = Replace(textoFinal, "\\""", """")
        
        IA = textoFinal
    Else
        IA = "Error leyendo respuesta."
    End If
    Exit Function

ErrorHandler:
    IA = "Error VBA: " & Err.Description
End Function
